pipeline {
    agent any
    environment {
        // VUE_DOCKER_IMAGE = 'node:18'
        SYMFONY_DOCKER_IMAGE = 'composer:2.6'
        // SYMFONY_DOCKER_IMAGE = 'symfony_backend'
        // DJANGO_DOCKER_IMAGE = 'python:3.10'
        // SPRINGBOOT_DOCKER_IMAGE = 'maven:3.8.7-eclipse-temurin-17'
    }
    stages {
        // stage('Build Symfony Backend') {
        //     agent {
        //         docker {
        //             image env.SYMFONY_DOCKER_IMAGE
        //             reuseNode true
        //         }
        //     }
        //     steps {
        //         sh '''
        //             cd project/backend/symfony
        //             composer install
        //         '''
        //     }
        // }

        stage('Build Symfony Backend') {
            steps {
                script {
                    // sh 'docker compose -f docker-compose.extra.yml build --no-cache symfony_backend'
                    // sh 'docker compose -f docker-compose.extra.yml down symfony_backend'
                    sh 'docker compose -f docker-compose.extra.yml up -d symfony_backend'
                    sh 'docker logs symfony_backend'
                    sh 'docker ps -a'
                    // sh 'docker compose -f docker-compose.extra.yml exec -T symfony_backend composer clear-cache'
                    // sh 'docker compose -f docker-compose.extra.yml exec -T symfony_backend composer install'
                    // sh 'docker compose -f docker-compose.extra.yml down symfony_backend'
                    // sh 'docker ps -a'
                    // sh 'docker logs symfony_backend'
                }
            }
        }
        stage('SonarQube Analysis') {
            steps {
                script {
                    sh '''
                    sonar-scanner \
                    -Dsonar.projectKey=symfony_project \
                    -Dsonar.sources=./project/backend/symfony \
                    -Dsonar.host.url=http://sonar:9000 \
                    -Dsonar.login=squ_077ab16d273236b5ce68c2a830e72efcd7f48c47 \
                    '''
                }
            }
        }
        stage('Package Symfony Backend') {
    steps {
        script {
            sh 'docker compose -f docker-compose.extra.yml down'

            // Leer la versión del .env
            // def version = sh(script: "grep VERSION .env | cut -d '=' -f2", returnStdout: true).trim()
            // env.APP_VERSION = version

            // Crear el ZIP sin vendor ni var
            sh """
            mkdir -p artifacts
            tar -czf artifacts/symfony_backend_2.tar.gz \
                --exclude=vendor --exclude=var --exclude=node_modules \
                project/backend/symfony
            """

            // Guardar el ZIP en Jenkins
            archiveArtifacts artifacts: "artifacts/symfony_backend_2.tar.gz", fingerprint: true
        }
    }
}

        // stage('Build Vue Front') {
        //     steps {
        //         script {
        //             sh 'docker compose -f docker-compose.extra.yml up -d front_micro'
        //             sh 'docker compose exec -T front_micro pnpm install && pnpm run build'
        //             sh 'docker compose -f docker-compose.extra.yml down front_micro'
        //         }
        //     }
        // }


        // stage('Build Symfony Backend') {[*]
        //     steps {
        //         sh 'docker-compose -f docker-compose.extra.yml up -d symfony_backend'
        //     }
        //     steps {
        //         sh 'docker-compose exec -T symfony_backend composer install'
        //     }
        //      steps {
        //         sh 'docker-compose -f docker-compose.extra.yml down symfony_backend'
        //     }
        // }

        // stage('Build Vue Front') {
        //     steps {
        //         sh 'docker-compose -f docker-compose.extra.yml up -d front_micro'
        //     }
        //     steps {
        //         sh 'docker-compose exec -T front_micro pnpm install && pnpm run build'
        //     }
        //      steps {
        //         sh 'docker-compose -f docker-compose.extra.yml down front_micro'
        //     }
        // }

//         stage('Build Django Backend') {
//             agent {
//                 docker {
//                     image env.DJANGO_DOCKER_IMAGE
//                     reuseNode true
//                 }
//             }
//             steps {
//                 sh '''
//                     cd project/backend/django
//                     pip install -r requirements.txt
//                 '''
//             }
//         }


//         stage('Build Spring Boot Backend') {
//             agent {
//                 docker {
//                     image env.SPRINGBOOT_DOCKER_IMAGE
//                     reuseNode true
//                 }
//             }
//             steps {
//                 sh '''
//                     cd project/backend/springboot
//                     ./mvnw clean package
//                 '''
//             }
//         }

        // stage('Build Vue Frontend') {
        //         agent {
        //             docker {
        //                 image env.VUE_DOCKER_IMAGE
        //                 reuseNode true
        //             }
        //         }
        //         steps {
        //             sh '''
        //                 cd project/front
        //                 npm install
        //                 npm run build
        //             '''
        //         }
        //     }
    }
//     post {
//         always {
// //          no --- archiveArtifacts artifacts: '**/target/*.jar', fingerprint: true
//             archiveArtifacts artifacts: 'project/front/dist/**', fingerprint: true
//         }
//         success {
//             echo 'Build and tests completed successfully!'
//             slackSend(channel: 'proyecto', color: 'good', message: "Build SUCCESS: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}\n${env.BUILD_URL}")
//             mail to: 'dev-team@example.com',
//               subject: "Build SUCCESS: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}",
//               body: "The build was successful!\nCheck it here: ${env.BUILD_URL}"
//         }
//         failure {
//             echo 'Build or tests failed.'
//             slackSend(channel: 'proyecto', color: 'danger', message: "Build FAILED: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}\n${env.BUILD_URL}")
//             mail to: env.EMAIL_RECIPIENT,
//                  subject: "Build FAILED: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}",
//                  body: "The build failed!\nCheck it here: ${env.BUILD_URL}"
//         }
//     }
}
