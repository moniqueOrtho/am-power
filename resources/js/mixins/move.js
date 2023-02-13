export default {
    methods: {
        arrayMove(array, from, to) {
            let target, increment, k;
            if( to === from ) return array;

            target = array[from];
            increment = to < from ? -1 : 1;

            for(k = from; k != to; k += increment){
                array[k] = array[k + increment];
            }
            array[to] = target;
            return array;
        },
        arrayAddTo(array, object, to) {
            array.unshift(object);
            return this.arrayMove(array, 0, to);
        }
    }
}
