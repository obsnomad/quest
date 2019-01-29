import React from 'react';
import {Button, ButtonGroup, Modal, ModalBody, ModalHeader} from 'reactstrap';
import InputPhone from '../components/InputPhone';

export default class ModalBooking extends React.Component {
    render() {
        return (
            <Modal
                isOpen={this.props.show}
                toggle={this.props.onHide}
                size="sm"
                id="modal-booking"
                aria-labelledby="contained-modal-title-sm"
            >
                {
                    this.props.booking &&
                    <ModalHeader toggle={this.props.onHide}>
                        Запись на квест<br/>«<span>{this.props.booking.questName}</span>»
                    </ModalHeader>
                }
                <ModalBody>
                    {
                        this.props.booking && this.props.booking.type === 'initial' &&
                        <form onSubmit={this.props.book} className="booking-form">
                            <div className="form-group">
                                <label htmlFor="booking-phone">
                                    Количество игроков
                                </label>
                                <ButtonGroup>
                                    <Button color="primary" onClick={() => this.props.setAmount(4)}
                                            active={this.props.amount === 4}>До 4</Button>
                                    <Button color="primary" onClick={() => this.props.setAmount(5)}
                                            active={this.props.amount === 5}>5</Button>
                                    <Button color="primary" onClick={() => this.props.setAmount(6)}
                                            active={this.props.amount === 6}>6</Button>
                                    <Button color="primary" onClick={() => this.props.setAmount(7)}
                                            active={this.props.amount === 7}>7</Button>
                                    <Button color="primary" onClick={() => this.props.setAmount(8)}
                                            active={this.props.amount === 8}>8</Button>
                                </ButtonGroup>
                            </div>
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
                                        <div>{this.props.booking.price + 300 * (this.props.amount - 4)} р.</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            {
                                !this.props.vkAccountId &&
                                <div className="form-group">
                                    <label htmlFor="booking-phone">
                                        Ваш номер телефона
                                        <div className="text-small">Мы позвоним Вам, чтобы подтвердить бронь.</div>
                                    </label>
                                    <InputPhone name="phone" id="booking-phone" className="form-control"
                                                onChange={this.props.setPhone} value={this.props.phone}/>
                                </div>
                            }
                            <div className="form-group">
                                <button type="submit" className="btn btn-lg btn-block btn-warning">
                                    Записаться
                                </button>
                            </div>
                            {
                                !this.props.vkAccountId &&
                                <div className="text-small">
                                    Перед отправкой проверьте, что верно ввели телефон. Иначе мы не сможем с Вами
                                    связаться и подтвердить запись на квест.
                                </div>
                            }
                        </form>
                    }
                    {
                        this.props.booking && this.props.booking.type === 'loading' &&
                        <div className="loader"/>
                    }
                </ModalBody>
            </Modal>
        );
    }
}
