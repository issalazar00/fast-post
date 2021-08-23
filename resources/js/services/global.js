const api = 'http://fast-post.com.devel/api';

export default {
    api: api,
    token: function () {
        return localStorage.getItem('token');
    },
    user: function () {
        return JSON.parse(localStorage.getItem('user'));
    },
    validatePermission: function (permissions, permission) {
        //console.log(typeof permissions);
        /*if(typeof permissions != 'array'){
            return false;
        }
        //const data = Array();

        //console.log(typeof data);

        permissions.forEach(item => {
            data.push(item);
        });*/

        var search = permissions.filter( (filtro) => {
            return filtro.name.match(permission);
        });
      
        return search.length > 0 ? true : false;

    },


};