import Comment from './Comment.js'
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

    this.json=null
    this.rows=[]

    //ADDINFO
    this.tothour='00:00'
    this.totmoney=0
    this.enddate='0000-00-00'
    this.calcbase='SSFV_DAY'
    this.basehours=9

    //COMMENTS
    this.comment=''
    this.comments=[]
    //this.comments.push(new Comment(1,'test','test','test'))
  }


  loadProjectInfo(){
    let p= $.ajax({
      url: 'h_project.php',
      dataType: 'json',
      data : { action: 'getinfo', us_id: us_id, p_id: this.id },
      type: 'POST',
      context: this
    })

    p=p.then((data) => {
      this.name=data.name
      this.job=data.job
      this.pay=data.pay
      this.company=data.company
      this.companyId=data.companyId
    })
    return p
  }

  loadProjectData(){
    let p= $.ajax({
      url: 'h_project.php',
      dataType: 'json',
      data : { action: 'load', p_id: this.id },
      type: 'POST',
      context: this
    })

    p=p.then((data) => {
      this.json=data.data
      this.comment=data.comment
    })
    return p
  }

  addRow(c,d){
    this.rows.push(new Row(c))
    this.rows[c].date = d
  }

  getComments(){
    let p=$.ajax({
      url: 'h_comments.php',
      dataType: 'json',
      data : { action: 'load', p_id: this.id },
      type: 'POST',
      context: this
    })
    p=p.then((data) => {
      let i=0
      for (let v of data) {
        this.comments[i]=new Comment(v.id, this.id, v.date, v.from, v.text)
        i++
      }
    })
    return p
  }

  get projHtml(){
    let o = ''
    for (let c of this.comments) {
      o += c.renderProject()
    }
    return o
  }

  get viewHtml(){
    let o = ''
    for (let c of this.comments) {
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
