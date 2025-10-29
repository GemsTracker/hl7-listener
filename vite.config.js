/* eslint-disable import/no-extraneous-dependencies */
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import gems from './resource/vite-plugin';

export default defineConfig({
  plugins: [
    gems({
      input: [
        // 'resource/js/vue-respondent.js',
        'resource/js/gems-vue.js',
        'resource/js/jquery.js',
        'resource/js/general.js',
        'resource/js/authenticated.js',
        'resource/css/gems.scss',
      ],
      refresh: true,
      publicHost: 'gemstracker.test',
    }),
    vue(),
  ],
});
