import fs from 'fs';
import { resolve } from 'node:path';

let exitHandlersBound = false;

const isIpv6 = ((address) => address.family === 'IPv6'
    // In node >=18.0 <18.4 this was an integer value. This was changed in a minor version.
    // See: https://github.com/laravel/vite-plugin/issues/103
    // @ts-ignore-next-line
    || address.family === 6);

const resolveDevServerUrl = ((address, serverConfig, userConfig) => {
  const protocol = serverConfig.server.https ? 'https' : 'http';
  const publicHost = userConfig.publicHost ?? null;
  const configHost = typeof serverConfig.server.host === 'string' ? serverConfig.server.host : null;
  const serverAddress = isIpv6(address) ? `[${address.address}]` : address.address;
  const host = publicHost ?? configHost ?? serverAddress;
  const { port } = address;

  return `${protocol}://${host}:${port}`;
});

export default function gems(userConfig) {
  const hotfile = userConfig.hotfile ?? 'public/vite-dev-server';

  return {
    config: () => ({
      publicDir: false,
      base: '',
      build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
          input: userConfig.input,
        },

      },
      server: {
        host: true,
        port: userConfig.server?.port ?? 5173,
        strictPort: userConfig.server?.strictPort ?? true,
        https: userConfig.server?.https ?? false,
      },
      resolve: {
        alias: {
          '@': '/resource/js',
          vue: 'vue/dist/vue.esm-bundler.js',
        },
      },
    }),
    configureServer(server) {
      server.httpServer?.once('listening', () => {
        const address = server.httpServer?.address();
        const viteDevServerUrl = resolveDevServerUrl(address, server.config, userConfig);
        fs.writeFileSync(hotfile, viteDevServerUrl);
      });

      if (!exitHandlersBound) {
        const clean = () => {
          if (fs.existsSync(hotfile)) {
            fs.rmSync(hotfile);
          }
        };

        process.on('exit', clean);
        process.on('SIGINT', process.exit);
        process.on('SIGTERM', process.exit);
        process.on('SIGHUP', process.exit);

        exitHandlersBound = true;
      }
    },
  };
}
