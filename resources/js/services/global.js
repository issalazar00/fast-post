const api = 'http://localhost/fast-post/public/api';

export default {
    api: api,
    token: function () {
        return localStorage.getItem('token');
    },
    user: function () {
        return JSON.parse(localStorage.getItem('user'));
    },
    validatePermission: function (permissions, permission) {
        
        if( permissions === undefined){ 
            permissions = this.user().permissions; //? this.user().permissions : [];
            //console.log(permissions);
        }
        //return true;
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