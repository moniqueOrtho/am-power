export default {
    namespaced: true,
    state: () => {
        return {
            images: []
        }
    },
    mutations: {
        setImages(state, payload) {
            state.images = payload;
        }
    },
    actions: {
        setImages(context, payload) {
            context.commit('setImages', payload);
        }
    },
    getters: {
        getImages(state) {
            return state.images;
        }
    }
}
