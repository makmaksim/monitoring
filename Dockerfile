FROM centos:latest 
MAINTAINER mayangindex

EXPOSE 80 
EXPOSE 5666
#RUN	rpm -Uvh http://dl.fedoraproject.org/pub/epel/7/x86_64/e/epel-release-7-9.noarch.rpm
#RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
RUN yum -y install nagios nagios-plugins-all nagios-plugins-nrpe nrpe php httpd

RUN chkconfig httpd on && chkconfig nagios on
RUN service httpd start && service nagios start
RUN dd if=/dev/zero of=/swap bs=1024 count=2097152
RUN mkswap /swap && chown root. /swap && chmod 0600 /swap && swapon /swap
RUN echo /swap swap swap defaults 0 0 >> /etc/fstab
RUN echo vm.swappiness = 0 >> /etc/sysctl.conf && sysctl -p