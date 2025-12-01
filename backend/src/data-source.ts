import { DataSource } from "typeorm";
import { User } from "./entity/user.entity";
import { Credential } from "./entity/credential.entity";
import { SeniorCitizen } from './entity/senior.entity';

export const AppDataSource = new DataSource({
    type: "mysql",
    url: process.env.DATABASE_URL,
    synchronize: true,
    logging: false,
    entities: [User, Credential],
});
