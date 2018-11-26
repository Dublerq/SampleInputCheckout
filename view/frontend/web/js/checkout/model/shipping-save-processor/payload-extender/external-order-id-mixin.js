define(
    ['mage/utils/wrapper'],
    function (wrapper) {
    'use strict';

    return function (target) {

        return wrapper.wrap(
            target,
            function (originalFunction, payload) {
            var newPayload = originalFunction(payload);
            var externalOrderIdValue = jQuery('[name="custom_attributes[external_order_id]"]').val();

            newPayload.addressInformation.extension_attributes.external_order_id = externalOrderIdValue;

            return newPayload;
            }
        );
    };
    }
);
