#!/bin/sh

# Asegurarse de que las dependencias estén instaladas
pnpm install
pnpm add -D ts-node
# Iniciar el servidor de desarrollo
pnpm run dev
