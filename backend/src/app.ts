// Load environment variables first
import dotenv from 'dotenv';
dotenv.config();

// Suppress the KafkaJS timeout warning and util._extend deprecation warning (known issues)
process.removeAllListeners('warning');
process.on('warning', (warning: any) => {
    // Suppress specific known warnings that don't affect functionality
    if (warning.name !== 'TimeoutNegativeWarning' &&
        warning.code !== 'DEP0060') {
        console.warn(warning.name, warning.message);
    }
});// Third party imports
import express from 'express';
import helmet from 'helmet';
import { errorHandler } from './middlewares/error.middleware';
import { verifyToken } from './middlewares/auth.middleware';
import { reqLogger } from './middlewares/req.middleware';
import { corsMiddleware } from './middlewares/cors.middleware';
// Local imports
import { logger } from './config/logger';
import { config } from './config';
import { indexRoutes, authRoutes, seniorRoutes } from './routes'
import { AppDataSource } from './data-source';

export const app = express();

// Middlewares
app.use(helmet());
app.use(corsMiddleware);
app.use(reqLogger);
app.use(express.json());
app.use(verifyToken);

// Routes
app.use('/', indexRoutes);
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/seniors', seniorRoutes);

// Error handling middleware (must be last)
app.use(errorHandler);


// Initialize database and other services
// If this file is run directly, initialize the DB and start server.
if (require.main === module) {
    AppDataSource.initialize()
        .then(async () => {
            logger.info('Data Source has been initialized!');
            const server = app.listen(config.PORT, () => {
                logger.info(
                    `${config.SERVICE_NAME} is running on http://localhost:${config.PORT}`,
                );
            });
        })
        .catch((err) => {
            logger.error('Error during Data Source initialization:', err);
        });
}

export default app;
