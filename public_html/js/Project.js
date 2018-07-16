import Chat from './Chat.js'
import Row from './Row.js'
import Expense from './Expense.js'

export default class Project {
  constructor(id){
    //PROJECT
    this.saved = true
    this.id=id
    this.user=''
    this.name=''
    this.job=''
    this.pay=0
    this.company=''
    this.companyId=''

    //Stunden
    this.json=null
    this.rows=[]

    //ADDINFO
    this.tothour='00:00'
    this.totmoney=0
    this.startdate= new Date('0000-00-00')
    this.enddate='0000-00-00'
    this.calcbase='SSFV_DAY'
    this.basehours=9

    //COMMENTS
    this.comment=''
    this.chats=[]

    //EXPENSES
    this.expenses=[]
  }


  loadProject(){
    let p= $.ajax({
      url: 'https://filmstunden.ch/api/v01/project/'+this.id,
      dataType: 'json',
      type: 'GET',
      context: this
    })

    p=p.then((data) => {
      this.startdate=new Date(data.startdate)
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

  getExpenses(){
    this.expenses=[]
    let p=$.ajax({
      url: 'https://filmstunden.ch/api/v01/expenses/'+this.id,
      dataType: 'json',
      type: 'GET',
      context: this
    })
    p=p.then((data) => {
      let i=0
      for (let v of data) {
        this.expenses[i]=new Expense(i+1,v.id,v.date,v.name,v.value,v.comment,v.img)
        i++
      }
    })
    return p
  }

  getChats(){
    let p=$.ajax({
      url: 'https://filmstunden.ch/api/v01/chats/'+this.id,
      dataType: 'json',
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

  get chatProjHtml(){
    let o = ''
    for (let c of this.chats) {
      o += c.renderProject()
    }
    return o
  }

  get chatViewHtml(){
    let o = ''
    for (let c of this.chats) {
      o += c.renderView()
    }
    return o
  }

  get expenseHtml(){
    if(this.expenses.length>0){
      let o = ''
      for (let c of this.expenses) {
        o += c.render()
      }
      return o
    }else{
      return '<tr><td colspan="5" align="center">Noch keine Spesen hinzugefügt</td></tr>'
    }
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
