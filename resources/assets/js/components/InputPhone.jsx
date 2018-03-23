import React from 'react';
import InputMask from 'react-input-mask';

export default class InputPhone extends React.Component {
    render() {
        return <InputMask {...this.props} type="text" mask="+7 (999) 999-99-99" maskChar="_"/>;
    }
}