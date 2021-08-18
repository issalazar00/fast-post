const api = 'http://fast-post.com.devel/api';

function getToken(){
    return localStorage.getItem('token');
}

function getUser(){
    return JSON.parse(localStorage.getItem('user'));
}

export default {
    api: api,
    token: getToken(),
    user: getUser()
};