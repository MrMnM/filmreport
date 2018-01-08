export default class Comment {
  constructor(id,p_id,date,from,text){
    this.id = id
    this.p_id = p_id
    this.date = moment(date).add(1,'hours').fromNow()
    this.from =from
    this.text=text
  }

  render(){
    let t=`
    <div class="media msg">
      <div class="media-body">
        <small class="pull-right time"><i class="fa fa-clock-o"></i> ${this.date}</small>
        <h5 class="media-heading">${this.from}</h5>
        <small class="col-lg-10 nopadding">${this.text}</small>
      </div>
    </div>`
    return t
  }
}
