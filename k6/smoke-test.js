/**
 * Smoke test — quick sanity check against one server.
 *
 * Usage:
 *   docker compose --profile testing run k6 run /scripts/smoke-test.js \
 *     -e BASE_URL=http://nginx:80
 */
import http from 'k6/http';
import { check, group } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8080';

export const options = {
    vus: 5,
    duration: '20s',
    thresholds: {
        http_req_failed:   ['rate<0.01'],
        http_req_duration: ['p(95)<2000'],
    },
};

export default function () {
    group('health', () => {
        const r = http.get(`${BASE_URL}/api/health`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('users', () => {
        const r = http.get(`${BASE_URL}/api/users`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('user detail', () => {
        const r = http.get(`${BASE_URL}/api/users/1`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('posts', () => {
        const r = http.get(`${BASE_URL}/api/posts`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('stats', () => {
        const r = http.get(`${BASE_URL}/api/stats`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });
}
