pipeline {
    agent any
    stages {
        stage('Build Symfony Backend') {
            steps {
                script {
                    sh 'docker compose -f docker-compose.extra.yml up -d symfony_backend'
                    sh 'docker logs symfony_backend'
                    sh 'docker ps -a'
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
                  def version = sh(script: "grep VERSION .env | cut -d '=' -f2", returnStdout: true).trim()
                  env.APP_VERSION = version
                  sh """
                  mkdir -p artifacts
                  tar -czf artifacts/symfony_backend_${env.APP_VERSION}.tar.gz \
                  tar -czf artifacts/symfony_backend_2.tar.gz \
                      --exclude=vendor --exclude=var --exclude=node_modules \
                      project/backend/symfony
                  """
                  archiveArtifacts artifacts: "artifacts/symfony_backend_${env.APP_VERSION}.tar.gz", fingerprint: true
              }
          }
        }

        stage('Upload to S3') {
          steps {
            script {
              withCredentials([
                  string(credentialsId: 'AWS_ACCESS_KEY', variable: 'AWS_ACCESS_KEY_ID'),
                  string(credentialsId: 'AWS_SECRET_KEY', variable: 'AWS_SECRET_ACCESS_KEY'),
              ])
              {
                sh """
                export AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
                export AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
                mkdir -p artifacts
                tar -czf artifacts/symfony_backend_2.tar.gz \
                    --exclude=vendor --exclude=var --exclude=node_modules \
                    project/backend/symfony

                aws s3 cp artifacts/symfony_backend_2.tar.gz s3://cubo-micro/
                """
              }
            }
          }
        }
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
