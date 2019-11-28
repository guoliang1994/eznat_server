import request from '@/utils/request'
const name = 'port_map'
export default {
  retrieve: function() {
    return request({
      url: name + '/retrieve',
      method: 'post',
      params: {}
    })
  },
  create: function(data) {
    return request({
      url: name + '/create',
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
  update: function(data) {
    return request({
      url: name + '/update',
      method: 'post',
      params: data
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
