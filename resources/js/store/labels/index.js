export default {
    namespaced: true,
    state: () => {
        return {
            translations: {}
        }
    },
    mutations: {
        setTranslations(state, payload) {
            state.translations = payload;
        }
    },
    actions: {
        setTranslations(context, payload) {
            context.commit('setTranslations', payload);
        }
    },
    getters: {
        getTranslations(state) {
            return state.translations;
        }
    }
}
