import React from 'react';
import ModalBooking from './ModalBooking';
import {toast, ToastContainer} from 'react-toastify';

const toastOptions = {
    position: toast.POSITION.BOTTOM_LEFT,
    hideProgressBar: true,
    autoClose: 5000,
    closeButton: '',
};

export default class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            schedule: null,
            view: 'loading',
            booking: null,
            bookingShow: false,
            phone: '',
            amount: 4,
        };
    }

    componentDidMount() {
        this.updateSchedule();
    }

    updateSchedule(loading = true) {
        let self = this;
        if (loading) {
            self.setState({
                view: 'loading',
            });
        }
        self.getData(scheduleUrl, {}, 'get', function (result) {
            self.setState({
                schedule: result.schedule,
                quest: result.quest,
                view: 'normal',
            });
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
                    price: time.price,
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

    setAmount(amount) {
        this.setState({
            amount: amount,
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
            amount: this.state.amount,
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
                toast.error(error, toastOptions);
                if (status === 403) {
                    self.setState({
                        booking: {},
                        bookingShow: false,
                    }, () => self.updateSchedule(false));
                }
                else {
                    self.setState({
                        booking: booking,
                    });
                }
            }
            else {
                toast.success(result.result, toastOptions);
                self.setState({
                    booking: {},
                    bookingShow: false,
                    phone: '',
                }, () => self.updateSchedule(false));
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
                    this.state.view === 'normal' && this.state.quest.working &&
                    <div className="pad">
                        <h3 className="quests-title">
                            Расписание и цены квеста «{this.state.quest.name}»
                        </h3>
                        <div className="schedule-timeline">
                            <ul className="schedule-prices" style={{
                                textAlign: 'left',
                            }}>
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
                            <div className="schedule-hint">
                                Цены указаны для команды до 4-х человек
                            </div>
                        </div>
                        <div className="schedule-quests">
                            {
                                Object.values(this.state.schedule.items).map((day, key) => {
                                    return (
                                        <div className="schedule-quests-item" key={key}>
                                            <div className="schedule-quests-item-date">
                                                <div>{day.weekDay}</div>
                                                {day.realDay}
                                            </div>
                                            <div className="schedule-quests-item-schedule">
                                                {
                                                    Object.values(day.items).map((time, timeKey) => {
                                                        return (
                                                            <div
                                                                className={time.booked ? '' : 'schedule-quests-item-price schedule-quests-item-price-' + this.state.schedule.prices.indexOf(time.price)}
                                                                key={timeKey} onClick={e => {
                                                                e.preventDefault();
                                                                this.setBooking(this.state.quest, time);
                                                            }}>
                                                                {time.time}
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </div>
                                        </div>
                                    )
                                })
                            }
                        </div>
                        <ModalBooking show={this.state.bookingShow} onHide={this.hideBooking.bind(this)}
                                      booking={this.state.booking} book={this.book.bind(this)}
                                      phone={this.state.phone}
                                      amount={this.state.amount}
                                      setPhone={this.setPhone.bind(this)}
                                      setAmount={this.setAmount.bind(this)}/>
                    </div>
                }
                <ToastContainer/>
            </div>
        );
    }
}
