import { update } from "lodash";

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
        },
        updateImage(state, payload) {
            let index = state.images.findIndex(x => x.id === payload.id);
            state.images[index] = payload;
        },
        deleteImage(state, id) {
            state.images = state.images.filter(x => x.id !== id);
        }
    },
    actions: {
        setImages(context, payload) {
            context.commit('setImages', payload);
        },
        updateImage(context, payload) {
            context.commit('updateImage', payload);
        },
        deleteImage(context, payload) {
            context.commit('deleteImage', payload);
        }
    },
    getters: {
        getImages(state) {
            return state.images;
        }
    }
}
