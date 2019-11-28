import request from '@/utils/request'
const name = 'server_manage'
export default {
  start: function() {
    return request({
      url: name + '/start',
      method: 'post',
      params: {}
    })
  },
  stop: function(data) {
    return request({
      url: name + '/stop',
      method: 'post',
      data
    })
  },
  restart: function(data) {
    return request({
      url: name + '/restart',
      method: 'post',
      data
    })
  },
  status: function(data) {
    return request({
      url: name + '/status',
      method: 'post',
      params: data
    })
  },
  reload: function(data) {
    return request({
      url: name + '/reload',
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
