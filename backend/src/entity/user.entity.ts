import {
    Entity,
    PrimaryGeneratedColumn,
    Column,
    CreateDateColumn,
    UpdateDateColumn,
    OneToOne,
} from "typeorm";

import { Credential } from "./credential.entity";

export enum UserRole {
    ADMIN = "admin",
    MODERATOR = "moderator",
    VISITOR = "visitor",
}

@Entity()
export class User {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'first_name' })
    firstName: string;

    @Column({ name: 'last_name' })
    lastName: string;

    @Column({ unique: true })
    email: string;

    @Column({
        type: "enum",
        enum: UserRole,
        default: UserRole.VISITOR,
    })
    role: UserRole

    @OneToOne(() => Credential, (credential) => credential.user)
    credential: Credential;

    @CreateDateColumn({ name: 'created_at' })
    createdAt: Date;

    @UpdateDateColumn({ name: 'updated_at' })
    updatedAt: Date;
}