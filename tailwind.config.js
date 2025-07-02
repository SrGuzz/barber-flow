import maryui from 'maryui/plugin';
export default {
  content: [
    './resources/**/*.{blade.php,js,vue}',
    './storage/framework/views/*.php',
    './vendor/livewire/livewire/**/*.php',
    './vendor/maryui/**/*.php',
  ],
  plugins: [maryui, require('daisyui')],
  safelist: [{ pattern: /(btn|alert|toast|modal|bg-|text-|border-).*/ }],
};
