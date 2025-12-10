# Installing Java & Maven on macOS

## 1. Install Java (OpenJDK 17)

```bash
brew install openjdk@17
```

Link it to system path (follow instructions from brew output, typically):
```bash
sudo ln -sfn /opt/homebrew/opt/openjdk@17/libexec/openjdk.jdk /Library/Java/JavaVirtualMachines/openjdk-17.jdk
```

## 2. Install Maven

```bash
brew install maven
```

## 3. Install MySQL

```bash
brew install mysql
brew services start mysql
```

## 4. Run the Project

Navigate to project root:
```bash
mvn spring-boot:run
```
