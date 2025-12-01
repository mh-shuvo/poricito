import { Request, Response } from 'express';
import { z } from 'zod';
import SeniorService from '../services/senior.service';
import { SeniorStatus } from '../entity/senior.entity';

const createSchema = z.object({
    name: z.string().min(1).max(200),
    district: z.string().max(100).optional(),
    upazila: z.string().max(100).optional(),
    union: z.string().max(100).optional(),
    address: z.string().max(500).optional(),
    details: z.string().max(2000).optional(),
    images: z.array(z.string()).optional(),
});

const updateSchema = createSchema.partial();

class SeniorController {
    private seniorService: SeniorService;

    constructor() {
        this.seniorService = new SeniorService();
    }

    async create(req: Request, res: Response) {
        const dto = createSchema.parse(req.body);
        const files = (req.files as any) || undefined;
        if (files) {
            dto.images = files.map((f: any) => `/uploads/seniors/${f.filename}`);
        }
        const result = await this.seniorService.createSenior(dto, req.userId);
        return res.status(201).json(result);
    }

    async list(req: Request, res: Response) {
        const list = await this.seniorService.listSeniors(req.userId, (req as any).userRole);
        return res.status(200).json(list);
    }

    async get(req: Request, res: Response) {
        const id = Number(req.params.id);
        const item = await this.seniorService.getSenior(id, req.userId, (req as any).userRole);
        return res.status(200).json(item);
    }

    async update(req: Request, res: Response) {
        const id = Number(req.params.id);
        const dto = updateSchema.parse(req.body);
        const files = (req.files as any) || undefined;
        if (files) {
            dto.images = files.map((f: any) => `/uploads/seniors/${f.filename}`);
        }
        const updated = await this.seniorService.updateSenior(id, dto, req.userId, (req as any).userRole);
        return res.status(200).json(updated);
    }

    async approve(req: Request, res: Response) {
        const id = Number(req.params.id);
        const updated = await this.seniorService.approveSenior(id, (req as any).userRole);
        return res.status(200).json(updated);
    }

    async cancel(req: Request, res: Response) {
        const id = Number(req.params.id);
        const updated = await this.seniorService.cancelSenior(id, (req as any).userRole);
        return res.status(200).json(updated);
    }

    async publish(req: Request, res: Response) {
        const id = Number(req.params.id);
        const updated = await this.seniorService.publishSenior(id, (req as any).userRole);
        return res.status(200).json(updated);
    }

    async remove(req: Request, res: Response) {
        const id = Number(req.params.id);
        await this.seniorService.removeSenior(id, (req as any).userRole);
        return res.status(204).send();
    }
}

export default SeniorController;
