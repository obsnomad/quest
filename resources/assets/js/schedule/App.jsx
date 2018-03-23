import React from 'react';
import OwlCarousel from 'react-owl-carousel';
import ModalBooking from './ModalBooking';

export default class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            schedule: null,
            scheduleItems: null,
            quests: null,
            questsActive: null,
            view: 'loading',
            booking: null,
            bookingShow: false,
            phone: '',
        };
    }

    componentDidMount() {
        this.updateSchedule();
    }

    updateSchedule() {
        let self = this;
        self.getData('/schedule', {}, 'get', function (result) {
            let first = Object.keys(result.schedule.items)[0];
            let scheduleItems = Object.values(result.schedule.items[first]);
            self.setState({
                schedule: result.schedule,
                scheduleItems: scheduleItems,
                quests: result.quests,
                view: 'normal',
            });
            self.setDay(scheduleItems[0].day);
        });
    }

    setDay(day) {
        let scheduleItems = this.state.scheduleItems.map(item => {
            item.active = (item.day === day);
            return item;
        });
        let questsActive = {};
        for (let i in this.state.schedule.items) {
            let item = this.state.schedule.items[i];
            if (item[day] && item[day].items) {
                questsActive[i] = Object.values(item[day].items);
            }
        }
        this.setState({
            scheduleItems: scheduleItems,
            questsActive: questsActive,
        });
    }

    setBooking(quest, time) {
        if (!time.booked) {
            this.setState({
                booking: {
                    type: 'initial',
                    questName: quest.name,
                    date: time.realDay,
                    time: time.time,
                    price: time.price + ' р.',
                    dateTime: time.date,
                    questId: quest.id,
                },
                bookingShow: true,
            });
        }
    }

    hideBooking() {
        this.setState({
            bookingShow: false,
        });
    }

    setPhone(e) {
        this.setState({
            phone: e.target.value,
        });
    }

    book(e) {
        e.preventDefault();
        let booking = this.state.booking;
        let self = this;
        self.setState({
            booking: {
                type: 'loading',
                questName: booking.questName,
            },
        });
        self.getData(bookRoute, {
            phone: this.state.phone,
            time: booking.dateTime,
            quest: booking.questId,
        }, 'post', function (result, status) {
            if (status !== 200) {
                let error = 'Возникла ошибка. Попробуйте ещё раз.';
                if (result.errors) {
                    let tempMessage = result.errors[Object.keys(result.errors)[0]][0];
                    if (tempMessage.length > 0) {
                        error = tempMessage;
                    }
                }
                else if (result.message && result.message.length > 0) {
                    error = result.message;
                }
                booking.error = error;
                self.setState({
                    booking: booking,
                });

            }
            else {
                booking.type = 'result';
                booking.result = result.result;
                self.setState({
                    booking: booking,
                });
            }
        });
    }

    getData(endpoint, data, method, callback) {
        method = method || 'post';
        let params = {
            method: method,
            headers: new Headers({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }),
        };
        if (method === 'post') {
            params.body = JSON.stringify(data || {});
        }
        fetch(endpoint, params)
            .then(response => {
                if (callback) {
                    let status = response.status;
                    response.json().then(function (result) {
                        callback(result, status);
                    });
                }
            });
    }

    render() {
        return (
            <div>
                {
                    this.state.view === 'loading' &&
                    <div className="loader"/>
                }
                {
                    this.state.view === 'normal' &&
                    <div>
                        <div className="schedule-timeline" data-fixable data-fixable-class="schedule-fixed">
                            <div className="carousel-container">
                                <OwlCarousel className="carousel-days owl-theme" items={10} margin={0} slideBy={1}
                                             nav={false}
                                             dots={false}
                                             responsive={{
                                                 0: {
                                                     items: 2
                                                 },
                                                 480: {
                                                     items: 5
                                                 },
                                                 768: {
                                                     items: 10
                                                 }
                                             }}>
                                    {
                                        this.state.scheduleItems && this.state.scheduleItems.map((item, key) => {
                                            return (
                                                <a href="#"
                                                   className={'carousel-days-item' + (item.active ? ' active' : '')}
                                                   key={key} onClick={e => {
                                                    e.preventDefault();
                                                    this.setDay(item.day);
                                                }}>
                                                    <h3>{item.weekDay}</h3>
                                                    <span>{item.realDay}</span>
                                                </a>
                                            );
                                        })
                                    }
                                </OwlCarousel>
                            </div>
                            <div className="container">
                                <ul className="schedule-prices">
                                    {
                                        this.state.schedule && this.state.schedule.prices && this.state.schedule.prices.map((price, key) => {
                                            return (
                                                <li className={'schedule-prices-' + key} key={key}>
                                                    {price} р.
                                                </li>
                                            );
                                        })
                                    }
                                </ul>
                            </div>
                        </div>
                        {
                            this.state.quests && this.state.quests.map((questGroup, key) => {
                                return (
                                    <div key={key}>
                                        <div className="wide-light pad">
                                            <div className="container">
                                                <h3>{questGroup[0].location.name}</h3>
                                                {questGroup[0].location.address}
                                            </div>
                                        </div>
                                        <div>
                                            <div className="container">
                                                <div className="schedule-quests">
                                                    {
                                                        questGroup.map((quest, questKey) => {
                                                            return (
                                                                <div className="schedule-quests-item"
                                                                     data-title={quest.name}
                                                                     data-id={quest.id} key={questKey}>
                                                                    <div className="schedule-quests-item-pic">
                                                                        <img src={quest.thumb_path} alt=""/>
                                                                    </div>
                                                                    <div className="schedule-quests-item-title">
                                                                        <a href={quest.url}>
                                                                            {quest.name}
                                                                        </a>
                                                                        <div>
                                                                            {quest.price_readable}
                                                                        </div>
                                                                    </div>
                                                                    <div id={'schedule-' + quest.id}
                                                                         className="schedule-quests-item-schedule">
                                                                        {
                                                                            !quest.working &&
                                                                            <span>Скоро открытие</span>
                                                                        }
                                                                        {
                                                                            quest.working && this.state.questsActive && this.state.questsActive[quest.id] &&
                                                                            this.state.questsActive[quest.id].map((time, timeKey) => {
                                                                                return (
                                                                                    <div
                                                                                        className={time.booked ? '' : 'schedule-quests-item-price schedule-quests-item-price-' + this.state.schedule.prices.indexOf(time.price)}
                                                                                        key={timeKey} onClick={e => {
                                                                                        e.preventDefault();
                                                                                        this.setBooking(quest, time);
                                                                                    }}>
                                                                                        {time.time}
                                                                                    </div>
                                                                                )
                                                                            })
                                                                        }
                                                                    </div>
                                                                </div>
                                                            );
                                                        })
                                                    }
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })
                        }
                        <ModalBooking show={this.state.bookingShow} onHide={this.hideBooking.bind(this)}
                                      booking={this.state.booking} book={this.book.bind(this)}
                                      setPhone={this.setPhone.bind(this)}/>
                    </div>
                }
            </div>
        );
    }
}
