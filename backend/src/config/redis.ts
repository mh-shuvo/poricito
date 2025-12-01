import { config } from ".";
import Redis from "ioredis";
import { logger } from "./logger";


class RedisClient {
    private instance: Redis;
    private isConnected: boolean = false;
    private REDIS_URL: string;
    private options: Record<string, unknown>;

    public constructor(REDIS_URL: string, options?: Record<string, unknown>) {
        this.REDIS_URL = REDIS_URL;
        this.options = options || {
            retryStrategy(times: number) {
                const delay = Math.min(times * 50, 2000);
                logger.warn(`Redis reconnect attempt #${times}, retrying in ${delay}ms`);
                return delay;
            },
            maxRetriesPerRequest: 3,
        };

        this.instance = new Redis(this.REDIS_URL, this.options);
        this.setupEventListeners();
    }

    public getInstance(): Redis {
        return this.instance;
    }
    private setupEventListeners() {

        this.instance.on("connect", () => {
            logger.info("Connecting to Redis...");
        });

        this.instance.on("ready", () => {
            this.isConnected = true;
            logger.info("Connected to Redis successfully.");
        });

        this.instance.on("error", (error) => {
            this.isConnected = false;
            logger.error(`Redis error: ${error.message}`);
        });

        this.instance.on("close", () => {
            this.isConnected = false;
            logger.warn("Redis connection closed.");
        });

        this.instance.on("reconnecting", () => {
            logger.info(`Reconnecting to Redis...`);
        });
    }
    public isReady(): boolean {
        return this.isConnected;
    }
    public async closeConnection() {
        if (this.instance) {
            try {
                await this.instance.quit();
                logger.info("Redis connection closed gracefully.");
            } catch (error) {
                logger.error(`Error closing Redis connection: ${error}`);
            }
        }
    }
}

const redisClient = new RedisClient(config.REDIS_URL)
export default redisClient.getInstance();