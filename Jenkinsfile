pipeline {
    agent any
    stages {
        stage('Build Symfony Backend') {
            steps {
                script {
                    // sh 'docker compose -f docker-compose.test.yml down sonar'
                    // sh 'docker compose -f docker-compose.test.yml build sonar'
                    // sh 'docker compose -f docker-compose.test.yml up -d sonar'
                    sh 'docker compose -f docker-compose.test.yml up -d symfony_tests'
                    sh 'docker logs symfony_tests'
                    sh 'docker logs sonar'
                    sh 'docker ps -a'
                }
            }
        }

        // stage('SonarQube Analysis localhost') {
        //     steps {
        //         script {
        //             sh '''
        //             # Esperar a que Sonar esté listo
        //             until curl -s http://localhost:9100/api/system/health | grep -q '"status":"UP"'; do
        //               echo "Waiting for SonarQube to be up..."
        //               sleep 5
        //             done

        //             # Ahora sí lanzar el análisis
        //             sonar-scanner \
        //               -Dsonar.projectKey=symfony_project \
        //               -Dsonar.sources=./project/backend/symfony \
        //               -Dsonar.host.url=http://localhost:9100 \
        //               -Dsonar.login=squ_077ab16d273236b5ce68c2a830e72efcd7f48c47
        //             '''
        //         }
        //     }
        // }

        stage('SonarQube Analysis test no found') {
            steps {
                script {
                    sh '''
                    sonar-scanner \
                    -Dsonar.projectKey=symfony_project \
                    -Dsonar.sources=./project/backend/symfony \
                    -Dsonar.host.url=http://localhost:9100 \
                    -Dsonar.login=squ_077ab16d273236b5ce68c2a830e72efcd7f48c47 \
                    '''
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
                def version = sh(script: "grep VERSION .env | cut -d '=' -f2", returnStdout: true).trim()
                env.APP_VERSION = version
                sh """
                export AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
                export AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
                mkdir -p artifacts
                tar -czf artifacts/symfony_backend_${env.APP_VERSION}.tar.gz \
                    --exclude=vendor --exclude=var --exclude=node_modules \
                    project/backend/symfony

                aws s3 cp artifacts/symfony_backend_${env.APP_VERSION}.tar.gz s3://cubo-micro/
                """
              }
            }
          }
        }

        stage('Test Chatbot FastAPI') {
          steps {
              script {
                  sh '''
                  docker compose -f docker-compose.test.yml up -d fastapi_tests
                  docker exec fastapi_tests pytest tests/
                  docker compose -f docker-compose.test.yml down
                  '''
              }
          }
      }

      stage('SonarQube Analysis FastAPI') {
          steps {
              script {
                  sh '''
                  sonar-scanner \
                    -Dsonar.projectKey=fastapi_project \
                    -Dsonar.sources=project/backend/fastapi \
                    -Dsonar.host.url=http://sonar:9000 \
                    -Dsonar.login=squ_077ab16d273236b5ce68c2a830e72efcd7f48c47 \
                  '''
              }
          }
      }

    }
    post {
        // always {
//          no --- archiveArtifacts artifacts: '**/target/*.jar', fingerprint: true
            // archiveArtifacts artifacts: 'project/front/dist/**', fingerprint: true
        // }
        success {
            echo 'Build and tests completed successfully!'
            slackSend(channel: 'proyecto', color: 'good', message: "Build SUCCESS: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}\n${env.BUILD_URL}")
            // mail to: 'dev-team@example.com',
            //   subject: "Build SUCCESS: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}",
            //   body: "The build was successful!\nCheck it here: ${env.BUILD_URL}"
        }
        failure {
            echo 'Build or tests failed.'
            slackSend(channel: 'proyecto', color: 'danger', message: "Build FAILED: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}\n${env.BUILD_URL}")
            // mail to: env.EMAIL_RECIPIENT,
            //      subject: "Build FAILED: Job ${env.JOB_NAME} - Build #${env.BUILD_NUMBER}",
            //      body: "The build failed!\nCheck it here: ${env.BUILD_URL}"
        }
    }
}
