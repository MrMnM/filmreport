import Chat from './Chat.js'
import Row from './Row.js'

export default class Project {
  constructor(id){
    //PROJECT
    this.id=id
    this.user=''
    this.name=''
    this.work=''
    this.pay=0
    this.company=''
    this.companyId=''

    //Stunden
    this.json=null
    this.rows=[]

    //ADDINFO
    this.tothour='00:00'
    this.totmoney=0
    this.startdate='000-00-00'
    this.enddate='0000-00-00'
    this.calcbase='SSFV_DAY'
    this.basehours=9

    //COMMENTS
    this.comment=''
    this.chats=[]
  }


  loadProject(){
    let p= $.ajax({
      url: 'https://filmstunden.ch/api/v01/project/'+this.id,
      dataType: 'json',
      type: 'GET',
      context: this
    })

    p=p.then((data) => {
      this.name=data.name
      this.job=data.job
      this.pay=data.pay
      this.company=data.company
      this.companyId=data.companyId
      this.json=data.data
      this.comment=data.comment
    })
    return p
  }

  addRow(c,d){
    this.rows.push(new Row(c))
    this.rows[c].date = d
  }

  getChats(){
    let p=$.ajax({
      url: 'https://filmstunden.ch/api/v01/chats/'+this.id,
      dataType: 'json',
      xhrFields: {withCredentials: true},
      type: 'GET',
      context: this
    })
    p=p.then((data) => {
      let i=0
      for (let v of data) {
        this.chats[i]=new Chat(v.id, this.id, v.date, v.from, v.text)
        i++
      }
    })
    return p
  }

  get projHtml(){
    let o = ''
    for (let c of this.chats) {
      o += c.renderProject()
    }
    return o
  }

  get viewHtml(){
    let o = ''
    for (let c of this.chats) {
      o += c.renderView()
    }
    return o
  }

  get addInfo(){
    let addInfo = {
      'tothour':this.tothour,
      'totmoney':this.totmoney,
      'enddate':this.enddate
    }
    return JSON.stringify(addInfo)
  }
}
