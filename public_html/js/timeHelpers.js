export function formatDate(d = new Date) {
  let month = String(d.getMonth() + 1)
  let day = String(d.getDate())
  const year = String(d.getFullYear())
  if (month.length < 2) month = '0' + month
  if (day.length < 2) day = '0' + day
  return `${year}-${month}-${day}`
}
export function timeToMins(time) {
  let b = time.split(':')
  return b[0]*60 + +b[1]
}
export function timeFromMins(mins) {
  function z(n){return (n<10? '0':'') + n}
  let h = (mins/60 |0) % 24
  let m = mins % 60
  return z(h) + ':' + z(m)
}
export function minsToHours(mins){
  function z(n){return (n<10? '0':'') + n}
  let h = (mins/60 |0)
  let m = mins % 60
  return z(h) + ':' + z(m)
}
export function subTimes(t0, t1) {
  return timeFromMins(timeToMins(t0) - timeToMins(t1))
}
export function addTimes(t0, t1){
  return timeFromMins(timeToMins(t0) + timeToMins(t1))
}
export function roundToTwo(num) {
  return +(Math.round(num + 'e+2')  + 'e-2')
}
export function formatDateFilename(date){
  let d = new Date(date)
  let month = '' + (d.getMonth() + 1)
  let day = '' + d.getDate()
  let year = '' + d.getFullYear()

  if (month.length < 2)
      month = '0' + month
  if (day.length < 2)
      day = '0' + day
  year = year.substring(2)
  return [year, month, day].join('')
}

export function formatDateSwiss(date){
  let d = new Date(date)
  let month = '' + (d.getMonth() + 1)
  let day = '' + d.getDate()
  let year = '' + d.getFullYear()
  if (month.length < 2)
      month = '0' + month
  if (day.length < 2)
      day = '0' + day
  //year = year.substring(2)
  return [day, month, year].join('.')
}
export function pad ( val ) { return val > 9 ? val : '0' + val }
