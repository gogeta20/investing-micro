#!/bin/sh
npm install -g pnpm vite
pnpm add -D ts-node

echo "Iniciando frontend con entorno: $MICRO_ENV"
pnpm run dev
