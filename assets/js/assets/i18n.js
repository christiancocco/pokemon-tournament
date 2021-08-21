import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

import it from './lang/it.json';
import en from './lang/en.json';

const languages = {
    it: it,
    en: en
};

const messages = Object.assign(languages);
const defaultLocale = 'en';
const defaultFallbackLocale = 'en';

export default new VueI18n({
    locale: defaultLocale,
    fallbackLocale: defaultFallbackLocale,
    messages
});