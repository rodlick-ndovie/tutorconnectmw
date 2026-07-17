// Extends Expo's default Metro config (required for Expo Router + asset handling).
const { getDefaultConfig } = require('expo/metro-config');

module.exports = getDefaultConfig(__dirname);
