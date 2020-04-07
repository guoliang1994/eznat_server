import request from '@/utils/request'
const name = 'server_manage'
export default {
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
  }
}
