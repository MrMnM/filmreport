import {roundToTwo, timeToMins, subTimes, timeFromMins, addDays} from './timeHelpers.js'
export default class Row {
  constructor (idNr,date) {
    this.id = idNr
    this.date = date
    this.start = '00:00'
    this.end = '00:00'
    this.work = ''
    this.break = '00:00'
    this.base = 0.0
    this.manualBase = false
    this.car = 0
    this.lunch = false
    this.workhours = '00:00'
    this.overtime=[0,0,0,0,0,0,0]
    this.night = 0
  }

  loadFromJSON(json){
    this.info=json.info
    this.id=json.id
    this.date =json.date
    this.start = json.start
    this.end = json.end
    this.work =json.work
    this.break =json.break
    this.base =json.base
    this.manualBase =json.manualBase
    this.car= json.car
    this.lunch =json.lunch
  }
  getWorkHours() {
    if (this.base==0.6) {
      this.workhours='05:00'
      return this.workhours
    }else if (this.base!=1) {
      this.workhours='05:00'
      return this.workhours
    }

    const brk = this.break //TODO: Get rid of Moment.js 
    const pause = brk.split(':')
    let difference = moment.utc(moment(this.end,'HH:mm').diff(moment(this.start,'HH:mm'))).format('HH:mm')
    let duration = moment.duration(difference)
    duration.subtract(pause[0] + ':00', 'hours')
    duration.subtract('00:' + pause[1], 'minutes')
    this.workhours = moment.utc(+duration).format('H:mm')
    return this.workhours
  }

  getBase() {
    if (this.manualBase == false){
      switch(this.work){
      case 'Dreh':
        this.base = 1.0
        break
      case 'Laden':
        this.base = 0.6
        break
      case 'Vorbereitung':
        this.base = 0.6
        break
      case 'Reisetag':
        this.base = 0.6
        break
      default:
        this.base = 0.6
      }
    }
    return this.base
  }


  calcOvertime(hour) { //TODO: Split in Get//SetOvertime
    let ret=0
    const workhours = this.getWorkHours()
    const currentHour = timeFromMins((hour-1)*60)
    if (workhours > currentHour){
      if(subTimes(workhours,currentHour) > '01:00'){
        if (workhours>'16:00' && currentHour == '15:00'){
          let mins = timeToMins(subTimes(workhours,currentHour))
          ret= roundToTwo(mins/60)
        } else{
          ret=1
        }
      } else {
        let mins = timeToMins(subTimes(workhours,currentHour))
        if (isNaN(mins) ||typeof ret == 'undefined') {
          ret=0
        }else{
          ret= roundToTwo(mins/60)
        }
      }
    }
    if (hour==10) {this.overtime[0]=ret}
    if (hour==11) {this.overtime[1]=ret}
    if (hour==12) {this.overtime[2]=ret}
    if (hour==13) {this.overtime[3]=ret}
    if (hour==14) {this.overtime[4]=ret}
    if (hour==15) {this.overtime[5]=ret}
    if (hour==16) {this.overtime[6]=ret}
    return ret
  }

  calcNighttime(){
    const MS2H = 3600000
    const D0 = 'January 1, 1970 '
    const nStart = new Date(D0+'23:00')
    const nEnd = addDays(new Date(D0+'05:00'),1)
    const start = new Date(D0+this.start)
    let end = new Date(D0+this.end)
    //Check if End is a Day later and add it
    if(timeToMins(this.start)>timeToMins(this.end)){end = addDays(end, 1)}
    const nightHours = Math.abs((nEnd-nStart)/MS2H)
    let nightwork = nightHours
    //Check wheter Start or end are in the night
    if(start>=nStart){nightwork = nightwork + (nStart-start)/MS2H}
    if(end<=nEnd){nightwork = nightwork + (end-nEnd)/MS2H}
    this.night = 0
    if(nightwork>0 && nightwork<=nightHours){this.night = roundToTwo(nightwork)}
    return this.night
  }
}
