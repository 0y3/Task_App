import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

export function showSuccessToast( msg: string ) {
    $toast.success(msg, {
        position: 'bottom-right',
        duration: 4000,
        dismissible: true,
    });
}

export function showErrorToast(msg: string) {
    $toast.error(msg, {
        position: 'bottom-right',
        duration: 5000,
        dismissible: true,
    });
}

export function showInfoToast(msg: string) {
    $toast.info(msg, {
        position: 'bottom-right',
        duration: 4000,
        dismissible: true,
    });
}

export function showWarningToast(msg: string) {
    $toast.warning(msg, {
        position: 'bottom-right',
        duration: 4000,
        dismissible: true,
    });
}

export function showDefaultToast(msg: string) {
    $toast.open({
        message: msg,
        position: 'bottom-right',
        duration: 4000,
        dismissible: true,
    });
}

