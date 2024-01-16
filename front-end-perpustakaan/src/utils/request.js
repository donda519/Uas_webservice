import axios from 'axios';
import Cookies from "js-cookie";
import router from "@/router";

const request = axios.create({
    baseURL: 'http://localhost:8003',
    timeout: 5000
})

request.interceptors.request.use(config => {

    config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
    config.headers['Authorization'] = Cookies.get('admin')? `Bearer  ${JSON.parse(Cookies.get('admin')).access_token}` : null;

    //const admin = Cookies.get('admin');
    //if(!admin) {
    //    router.push('/login')
    //}

    return config
}, error => {
    return Promise.reject(error)
});

request.interceptors.response.use(
    response => {
        let res = response.data;
        if (typeof res === 'string') {
            res = res ? JSON.parse(res) : res
        }
        return res;
    },
    error => {
        if (`${error}`.includes('401')) {
            console.log('err ' + error) // for debug
            Cookies.remove('admin');
            router.push('/login');
        }
        return Promise.reject(error)
    }
)


export default request