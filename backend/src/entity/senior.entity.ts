import {
    Entity,
    PrimaryGeneratedColumn,
    Column,
    CreateDateColumn,
    UpdateDateColumn,
    ManyToOne,
} from 'typeorm';

import { User } from './user.entity';

export enum SeniorStatus {
    PENDING = 'pending',
    APPROVED = 'approved',
    CANCELED = 'canceled',
}

@Entity()
export class SeniorCitizen {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'name' })
    name: string;

    @Column({ nullable: true })
    district: string;

    @Column({ nullable: true })
    upazila: string;

    @Column({ nullable: true })
    union: string;

    @Column({ type: 'text', nullable: true })
    address: string;

    @Column({ type: 'text', nullable: true })
    details: string;

    // store image paths as simple-array
    @Column('simple-array', { nullable: true })
    images: string[];

    @Column({
        type: 'enum',
        enum: SeniorStatus,
        default: SeniorStatus.PENDING,
    })
    status: SeniorStatus;

    @Column({ default: false })
    isPublished: boolean;

    @ManyToOne(() => User, (user) => user.id, { eager: true })
    createdBy: User;

    @CreateDateColumn({ name: 'created_at' })
    createdAt: Date;

    @UpdateDateColumn({ name: 'updated_at' })
    updatedAt: Date;
}
