FROM rabbitmq:3-management
RUN rabbitmq-plugins enable --offline rabbitmq_management
COPY ./conf/rabbitmq/rabbitmq.conf /etc/rabbitmq/rabbitmq.conf
