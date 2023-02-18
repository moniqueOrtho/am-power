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
        storeImage(state, payload) {
            let index = state.images.findIndex(x => x.id === payload.id);
            if(index > -1) {
               this.commit('deleteImage', payload.id)
            }
            state.images.push(payload);
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
        storeImage(context, payload) {
            context.commit('storeImage', payload)
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
