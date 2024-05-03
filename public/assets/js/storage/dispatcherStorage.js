/**
 * custom spinner Js 
 * Store all portal localstorage
 */
const dispatcherStorage = {
    setStorageAll(type_id,slot_id,type) {
       
    },
    setStorageSingle(item,value) {
        localStorage.setItem(item,value);
    },
    removeStorageAll(){
        localStorage.clear();
    },
    removeStorageSingle(item){
        localStorage.removeItem(item);
    },
    getStorage(item){
        var returnValue = localStorage.getItem(String(item));
        return (returnValue) ? returnValue :'';
    }
}