import React from 'react';
import Modal from 'react-bootstrap-modal';
import InputPhone from '../components/InputPhone';

export default class ModalBooking extends React.Component {
    render() {
        return (
            <Modal
                show={this.props.show}
                onHide={this.props.onHide}
                small={true}
                aria-labelledby="contained-modal-title-sm"
            >
                {
                    this.props.booking &&
                    <Modal.Header closeButton>
                        <Modal.Title id="contained-modal-title-sm">
                            Запись на квест<br/>«<span>{this.props.booking.questName}</span>»
                        </Modal.Title>
                    </Modal.Header>
                }
                <Modal.Body>
                    {
                        this.props.booking && this.props.booking.type === 'initial' &&
                        <div className="booking-form">
                            <table className="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>
                                        Дата
                                        <div>{this.props.booking.date}</div>
                                    </td>
                                    <td>
                                        Время
                                        <div>{this.props.booking.time}</div>
                                    </td>
                                    <td>
                                        Цена
                                        <div>{this.props.booking.price}</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <form onSubmit={this.props.book}>
                                <div className="form-group">
                                    <label htmlFor="booking-phone">Ваш номер телефона. Мы позвоним Вам, чтобы
                                        подтвердить бронь.</label>
                                    <InputPhone name="phone" id="booking-phone" className="form-control"
                                                onChange={this.props.setPhone} value={this.props.phone}/>
                                </div>
                                <div>
                                    <button type="submit" className="btn btn-lg btn-block btn-warning">
                                        Записаться
                                    </button>
                                </div>
                            </form>
                        </div>
                    }
                    {
                        this.props.booking && this.props.booking.type === 'loading' &&
                        <div className="loader"/>
                    }
                </Modal.Body>
            </Modal>
        );
    }
}
