/// <reference types="jest" />
import request from 'supertest';
import app from '../app';

// Mock auth middleware to inject userId and userRole
jest.mock('../middlewares/auth.middleware', () => ({
    verifyToken: (req: any, res: any, next: any) => {
        req.userId = 1;
        req.token = 'test-token';
        req.userRole = 'visitor';
        return next();
    },
}));

// Mock SeniorService methods
jest.mock('../services/senior.service');
const SeniorService = require('../services/senior.service');

describe('Seniors routes (mocked)', () => {
    beforeAll(() => {
        SeniorService.mockImplementation(() => ({
            listSeniors: jest.fn().mockResolvedValue([{ id: 1, name: 'John Doe' }]),
            getSenior: jest.fn().mockResolvedValue({ id: 1, name: 'John Doe' }),
            createSenior: jest.fn().mockResolvedValue({ id: 2, name: 'Jane' }),
        }));
    });

    test('GET /api/v1/seniors returns list', async () => {
        const res = await request(app).get('/api/v1/seniors');
        expect(res.status).toBe(200);
        expect(Array.isArray(res.body)).toBe(true);
    });

    test('GET /api/v1/seniors/:id returns item', async () => {
        const res = await request(app).get('/api/v1/seniors/1');
        expect(res.status).toBe(200);
        expect(res.body).toHaveProperty('id', 1);
    });

    test('POST /api/v1/seniors (create) works with mocked service', async () => {
        const res = await request(app)
            .post('/api/v1/seniors')
            .send({ name: 'New Senior' })
            .set('Authorization', 'Bearer test-token');
        expect(res.status).toBe(201);
        expect(res.body).toHaveProperty('id');
    });
});
