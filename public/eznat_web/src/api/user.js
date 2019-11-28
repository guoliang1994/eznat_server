import request from '@/utils/request'

export function login(data) {
  return request({
    url: 'guard/login',
    method: 'post',
    data
  })
}

export function getInfo() {
  return request({
    url: 'guard/getUserInfo',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/guard/logout',
    method: 'post'
  })
}
