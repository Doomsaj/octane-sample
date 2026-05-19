import http from 'k6/http';
import { check, group, sleep } from 'k6';

const FPM_URL    = __ENV.FPM_URL    || 'http://localhost:8080';
const OCTANE_URL = __ENV.OCTANE_URL || 'http://localhost:8081';

// Two back-to-back scenarios so results are visually comparable in Grafana.
// FPM runs first (0–1m45s), Octane starts 2 minutes later.
export const options = {
    scenarios: {
        fpm: {
            executor: 'ramping-vus',
            startVUs: 1,
            stages: [
                { duration: '30s', target: 50 },
                { duration: '60s', target: 50 },
                { duration: '15s', target: 0 },
            ],
            env: { SERVER: 'fpm', BASE_URL: FPM_URL },
            tags: { server: 'fpm' },
        },
        octane: {
            executor: 'ramping-vus',
            startTime: '2m',
            startVUs: 1,
            stages: [
                { duration: '30s', target: 50 },
                { duration: '60s', target: 50 },
                { duration: '15s', target: 0 },
            ],
            env: { SERVER: 'octane', BASE_URL: OCTANE_URL },
            tags: { server: 'octane' },
        },
    },
    thresholds: {
        'http_req_duration{server:octane}': ['p(95)<500'],
        'http_req_failed{server:fpm}':     ['rate<0.01'],
        'http_req_failed{server:octane}':  ['rate<0.01'],
    },
};

export default function () {
    const base = __ENV.BASE_URL;

    group('health', () => {
        const r = http.get(`${base}/api/health`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('users list', () => {
        const r = http.get(`${base}/api/users`);
        check(r, {
            'status 200': (r) => r.status === 200,
            'has data':   (r) => JSON.parse(r.body).data !== undefined,
        });
    });

    group('user detail', () => {
        const id = Math.floor(Math.random() * 100) + 1;
        const r  = http.get(`${base}/api/users/${id}`);
        check(r, { 'status 200 or 404': (r) => r.status === 200 || r.status === 404 });
    });

    group('posts list', () => {
        const r = http.get(`${base}/api/posts`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    group('stats', () => {
        const r = http.get(`${base}/api/stats`);
        check(r, { 'status 200': (r) => r.status === 200 });
    });

    sleep(0.1);
}
