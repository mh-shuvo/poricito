import { Repository } from 'typeorm';
import { AppDataSource } from '../data-source';
import { SeniorCitizen, SeniorStatus } from '../entity/senior.entity';
import { User, UserRole } from '../entity/user.entity';
import { createError } from '../utils';

interface CreateSeniorDto {
    name: string;
    district?: string;
    upazila?: string;
    union?: string;
    address?: string;
    details?: string;
    images?: string[];
}

class SeniorService {
    seniorRepository: Repository<SeniorCitizen>;
    userRepository: Repository<User>;

    constructor() {
        this.seniorRepository = AppDataSource.getRepository(SeniorCitizen);
        this.userRepository = AppDataSource.getRepository(User);
    }

    async createSenior(dto: CreateSeniorDto, creatorId: number) {
        const user = await this.userRepository.findOneBy({ id: creatorId });
        if (!user) throw createError('creator not found', 404);

        const senior = new SeniorCitizen();
        senior.name = dto.name;
        senior.district = dto.district || '';
        senior.upazila = dto.upazila || '';
        senior.union = dto.union || '';
        senior.address = dto.address || '';
        senior.details = dto.details || '';
        senior.images = dto.images || [];
        senior.createdBy = user;

        return await this.seniorRepository.save(senior);
    }

    async listSeniors(requesterId?: number, requesterRole?: string) {
        // Visitors (not authenticated) or general public see only published and approved
        if (!requesterRole) {
            return this.seniorRepository.find({ where: { isPublished: true, status: SeniorStatus.APPROVED } });
        }

        if (requesterRole === UserRole.ADMIN || requesterRole === UserRole.MODERATOR) {
            return this.seniorRepository.find();
        }

        // visitor: show published ones + those created by the visitor
        return this.seniorRepository.createQueryBuilder('s')
            .where('s.isPublished = :published AND s.status = :approved', { published: true, approved: SeniorStatus.APPROVED })
            .orWhere('s.createdById = :creatorId', { creatorId: requesterId })
            .getMany();
    }

    async getSenior(id: number, requesterId?: number, requesterRole?: string) {
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw createError('not found', 404);

        if (senior.isPublished && senior.status === SeniorStatus.APPROVED) return senior;

        if (requesterRole === UserRole.ADMIN || requesterRole === UserRole.MODERATOR) return senior;

        if (requesterId && senior.createdBy && senior.createdBy.id === requesterId) return senior;

        throw new Error('forbidden');
    }

    async updateSenior(id: number, dto: Partial<CreateSeniorDto>, requesterId: number, requesterRole?: string) {
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw new Error('not found');

        // visitor can update only their own and when pending
        if (requesterRole === UserRole.VISITOR) {
            if (!senior.createdBy || senior.createdBy.id !== requesterId) throw createError('forbidden', 403);
            if (senior.status !== SeniorStatus.PENDING) throw createError('cannot edit after moderation', 400);
        }

        // moderator can update unless approved
        if (requesterRole === UserRole.MODERATOR) {
            if (senior.status === SeniorStatus.APPROVED) throw createError('cannot edit after approved', 400);
        }

        // admin can always update

        if (dto.name !== undefined) senior.name = dto.name;
        if (dto.district !== undefined) senior.district = dto.district;
        if (dto.upazila !== undefined) senior.upazila = dto.upazila;
        if (dto.union !== undefined) senior.union = dto.union;
        if (dto.address !== undefined) senior.address = dto.address;
        if (dto.details !== undefined) senior.details = dto.details;
        if (dto.images !== undefined) senior.images = dto.images || [];

        return this.seniorRepository.save(senior);
    }

    async approveSenior(id: number, approverRole?: string) {
        if (approverRole !== UserRole.ADMIN && approverRole !== UserRole.MODERATOR) throw createError('forbidden', 403);
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw createError('not found', 404);
        senior.status = SeniorStatus.APPROVED;
        return this.seniorRepository.save(senior);
    }

    async cancelSenior(id: number, cancellerRole?: string) {
        if (cancellerRole !== UserRole.ADMIN && cancellerRole !== UserRole.MODERATOR) throw createError('forbidden', 403);
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw createError('not found', 404);
        senior.status = SeniorStatus.CANCELED;
        return this.seniorRepository.save(senior);
    }

    async publishSenior(id: number, publisherRole?: string) {
        if (publisherRole !== UserRole.ADMIN) throw createError('forbidden', 403);
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw createError('not found', 404);
        if (senior.status !== SeniorStatus.APPROVED) throw createError('only approved can be published', 400);
        senior.isPublished = true;
        return this.seniorRepository.save(senior);
    }

    async removeSenior(id: number, requesterRole?: string) {
        if (requesterRole !== UserRole.ADMIN) throw createError('forbidden', 403);
        const senior = await this.seniorRepository.findOne({ where: { id } });
        if (!senior) throw createError('not found', 404);
        return this.seniorRepository.remove(senior);
    }
}

export default SeniorService;
