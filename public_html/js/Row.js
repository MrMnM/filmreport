import {roundToTwo, timeToMins, subTimes, timeFromMins} from './timeHelpers.js'
export default class Row {
  constructor (idNr) {
    this.id = idNr
    this.date = null
    this.start = '00:00'
    this.end = '00:00'
    this.work = ''
    this.break = '00:00'
    this.base = 0.0
    this.manualBase = false
    this.car = 0
    this.lunch = false
    this.workhours = '00:00'
    // TODO Overtime in Array perhour
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

    let brk = this.break
    let pause = brk.split(':')
    var difference = moment.utc(moment(this.end,'HH:mm').diff(moment(this.start,'HH:mm'))).format('HH:mm')
    var duration = moment.duration(difference)
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

  getOvertime(hour) {
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

  getNightHours() {
    //let hours=0
    let nightStart = moment('23:00','HH:mm')
    let nightEnd = moment('05:00','HH:mm').add(1, 'd')
    let start = moment(this.start,'HH:mm')
    let end = moment(this.end,'HH:mm')
    if(timeToMins(this.start)>timeToMins(this.end)){
      end.add(1,'d')
    }
    let nighttime = nightStart.twix(nightEnd)
    let worktime = start.twix(end)
    let differ = worktime.difference(nighttime)
    if (differ.length) {
      let difference = moment.utc(moment(this.end,'HH:mm').diff(moment(this.start,'HH:mm'))).format('HH:mm')
      let duration = moment.duration(difference)
      duration.subtract(differ[0].length('minutes'),'m')
      this.night = roundToTwo(moment.utc(+duration).format('m')/60)
    }else{
      let difference = moment.utc(moment(this.end,'HH:mm').diff(moment(this.start,'HH:mm'))).format('HH:mm')
      let duration = moment.duration(difference)
      this.night = roundToTwo(moment.utc(+duration).format('m')/60)
    }
    return this.night
  }
}
