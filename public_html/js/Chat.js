export default class Chat {
  constructor(id,p_id,date,from,text){
    this.id = id
    this.p_id = p_id
    this.date = moment(date).fromNow()
    this.from =from
    this.text=text
  }

  renderProject(){
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

  renderView(){
    let t=`
    <div class="media msg" style="border-bottom: 1px solid #e7e7e7; margin-top: 5px;">
      <div class="media-body">
        <div style="color: #BBB; margin-bottom: -1.5em; margin-left:10px;"><strong>${this.from}</strong></div>
            <span class="pull-right text-muted" style="margin-right: 20px;">
              <small><em><i class="fa fa-clock-o"></i> ${this.date}</em></small>
            </span>
          </div>
          <div style="color: #777; padding: 5px; padding-left:10px;">${this.text}</div>
    </div>
    </div>`
    return t
  }
}
