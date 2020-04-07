import request from '@/utils/request'
const name = 'client'
export default {
  retrieve: function(data) {
    return request({
      url: name + '/retrieve',
      method: 'post',
      params: data
    })
  },
  my_client: function(data) {
    return request({
      url: name + '/my_client',
      method: 'post',
      params: data
    })
  },
  update_or_create: function(data) {
    return request({
      url: name + '/update_or_create',
      method: 'post',
      params: data
    })
  },
  read: function(id) {
    return request({
      url: name + '/read',
      method: 'get',
      params: {
        id
      }
    })
  },
  delete: function(ids) {
    return request({
      url: name + '/delete',
      method: 'post',
      params: {
        id: ids
      }
    })
  }
}
