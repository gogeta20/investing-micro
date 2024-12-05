pipeline {
    agent any
    environment {
        VUE_DOCKER_IMAGE = 'node:18'
        SYMFONY_DOCKER_IMAGE = 'composer:2.6'
        DJANGO_DOCKER_IMAGE = 'python:3.10'
        SPRINGBOOT_DOCKER_IMAGE = 'maven:3.8.7-eclipse-temurin-17'
    }
    stages {
        stage('Build Symfony Backend') {
            agent {
                docker {
                    image env.SYMFONY_DOCKER_IMAGE
                    reuseNode true
                }
            }
            steps {
                sh '''
                    cd project/backend/symfony
                    composer install
                '''
            }
        }

        stage('Build Django Backend') {
            agent {
                docker {
                    image env.DJANGO_DOCKER_IMAGE
                    reuseNode true
                }
            }
            steps {
                sh '''
                    cd project/backend/django
                    pip install -r requirements.txt
                '''
            }
        }


        stage('Build Spring Boot Backend') {
            agent {
                docker {
                    image env.SPRINGBOOT_DOCKER_IMAGE
                    reuseNode true
                }
            }
            steps {
                sh '''
                    cd project/backend/springboot
                    ./mvnw clean package
                '''
            }
        }

        stage('Build Vue Frontend') {
                agent {
                    docker {
                        image env.VUE_DOCKER_IMAGE
                        reuseNode true
                    }
                }
                steps {
                    sh '''
                        cd project/front/vue
                        npm install
                        npm run build
                    '''
                }
            }


    }
    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', fingerprint: true
        }
        success {
            echo 'Build and tests completed successfully!'
        }
        failure {
            echo 'Build or tests failed.'
        }
    }
}
