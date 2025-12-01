import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import SeniorController from '../controllers/senior.controller';

const seniorRoutes = Router();
const controller = new SeniorController();

// setup upload storage
const uploadDir = path.join(process.cwd(), 'uploads', 'seniors');
if (!fs.existsSync(uploadDir)) fs.mkdirSync(uploadDir, { recursive: true });

const storage = multer.diskStorage({
    destination: (req: any, file: any, cb: any) => cb(null, uploadDir),
    filename: (req: any, file: any, cb: any) => cb(null, `${Date.now()}-${file.originalname}`),
});

const upload = multer({ storage });

// Public list and get
seniorRoutes.get('/', controller.list.bind(controller));
seniorRoutes.get('/:id', controller.get.bind(controller));

// Protected endpoints (require authentication)
seniorRoutes.post('/', upload.array('images', 10), controller.create.bind(controller));
seniorRoutes.patch('/:id', upload.array('images', 10), controller.update.bind(controller));

// Moderator / Admin actions
seniorRoutes.post('/:id/approve', controller.approve.bind(controller));
seniorRoutes.post('/:id/cancel', controller.cancel.bind(controller));

// Admin-only
seniorRoutes.post('/:id/publish', controller.publish.bind(controller));
seniorRoutes.delete('/:id', controller.remove.bind(controller));

export { seniorRoutes };
