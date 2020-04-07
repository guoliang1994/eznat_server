import request from '@/utils/request'
const name = 'user'
export default {
  retrieve: function(params) {
    return request({
      url: name + '/retrieve',
      method: 'post',
      params
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
  update_or_create: function(data) {
    return request({
      url: name + '/update_or_create',
      method: 'post',
      params: data
    })
  },
  frozen: function(row) {
    return request({
      url: name + '/frozen/' + row.id + '/' + (1 - row.frozen),
      method: 'post',
      params: {}
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
