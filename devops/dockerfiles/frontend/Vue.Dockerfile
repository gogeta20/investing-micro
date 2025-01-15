FROM node:18-alpine
RUN npm install -g pnpm vite
WORKDIR /app

COPY project/front/package.json project/front/pnpm-lock.yaml ./
RUN pnpm install
COPY project/front/ .

EXPOSE 5173
CMD ["pnpm", "run", "dev"]
