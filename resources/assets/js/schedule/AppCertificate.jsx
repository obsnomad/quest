import React from 'react';
import {toast, ToastContainer} from 'react-toastify';
import {Button, Form, FormGroup, Input, Label} from 'reactstrap';
import InputPhone from '../components/InputPhone';

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
            loading: false,
            email: '',
            phone: '',
            vk: '',
            name: '',
        };
        this.change = this.change.bind(this);
    }

    change(e) {
        e.preventDefault();
        const obj = {};
        obj[e.target.name] =  e.target.value;
        this.setState(obj);
    }

    submit(e) {
        e.preventDefault();
        let self = this;
        self.setState({
            loading: true,
        });
        self.getData(giftRoute, {
            email: this.state.email,
            phone: this.state.phone,
            vk: this.state.vk,
            name: this.state.name,
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
                self.setState({
                    loading: false,
                });
            }
            else {
                toast.success(result.result, toastOptions);
                self.setState({
                    loading: false,
                    email: '',
                    phone: '',
                    vk: '',
                    name: '',
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
                    this.state.loading &&
                    <div className="loader"/>
                }
                {
                    !this.state.loading &&
                    <Form className="pad text-left" onSubmit={this.submit.bind(this)}>
                        <FormGroup row>
                            <Label for="email" className="col-sm-4 col-form-label">Почта</Label>
                            <div className="col-sm-8">
                                <Input type="email" name="email" id="email"
                                       onChange={this.change}
                                       value={this.state.email}/>
                            </div>
                        </FormGroup>
                        <FormGroup row>
                            <Label for="phone" className="col-sm-4 col-form-label">Телефон</Label>
                            <div className="col-sm-8">
                                <InputPhone name="phone" id="phone" className="form-control"
                                            onChange={this.change}
                                            value={this.state.phone}/>
                            </div>
                        </FormGroup>
                        <FormGroup row>
                            <Label for="vk" className="col-sm-4 col-form-label">Страница VK</Label>
                            <div className="col-sm-8">
                                <Input type="text" name="vk" id="vk"
                                       onChange={this.change}
                                       value={this.state.vk}/>
                            </div>
                        </FormGroup>
                        <FormGroup row>
                            <Label for="name" className="col-sm-4 col-form-label">Имя</Label>
                            <div className="col-sm-8">
                                <Input type="text" name="name" id="name"
                                       onChange={this.change}
                                       value={this.state.name}/>
                            </div>
                        </FormGroup>
                        <FormGroup className="text-right">
                            <Button type="submit" color="primary">Отправить</Button>
                        </FormGroup>
                    </Form>
                }
                <ToastContainer/>
            </div>
        );
    }
}
